import React from 'react';
import { Statistic, Card, Button, Icon, Dimmer, Loader } from 'semantic-ui-react';
import { Link } from 'react-router-dom';
import InputSliderRange from './InputSliderRange';

export default function SensorCard({ sensor, deleteSensor, checkStatusSensor, setStatusSensor }) {
    let activeIcon, activeDesc, tempStatus, tempColor, tempContent, switchableButton;

    if (!sensor.name) {
        sensor.name = "Name not set";
    }

    if (!sensor.room) {
        sensor.room = "Room not set";
        sensor.floor = "No floors"
    }

    if (sensor.active) {
        activeIcon = <Icon name='circle' color='green' />;
        activeDesc = 'Active';
    } else {
        activeIcon = <Icon name='circle' color='red' />;
        activeDesc = 'Inactive';
    }

    if (sensor.status === 0) {
        tempColor = 'green';
        tempStatus = 1;
        tempContent = 'Turn on';
    } else {
        tempColor = 'red';
        tempStatus = 0;
        tempContent = 'Turn off';
    }
    if (sensor.switchable) {
        switchableButton =
            <div>
                <Button content={tempContent} icon='power' color={tempColor} onClick={() => { setStatusSensor(sensor, tempStatus) }}></Button>
                <br />
                <br />
            </div>
        if (sensor.adjustable) {
            let sliderId = 'slider-' + sensor.id
            switchableButton =
                <div>
                    <InputSliderRange
                        sensor={sensor}
                        min='0'
                        max='255'
                        current={sensor.status}
                        step='1'
                        className='set-status-slider'
                        id={sliderId}
                        setStatusSensor={setStatusSensor}
                    />
                </div>
        }
    } else {
        switchableButton =
            <div>
                <Button content='Refresh' icon='refresh' primary onClick={() => { checkStatusSensor(sensor) }}></Button>
                <br />
                <br />
            </div>
    }

    return (
        <Card>
            <Dimmer invert='true' active={sensor.loading}>
                <Loader content='Loading...' />
            </Dimmer>
            <Card.Content>
                <Statistic size='small' floated='right'>
                    <Statistic.Value>{activeIcon}</Statistic.Value>
                    <Statistic.Label>{activeDesc}</Statistic.Label>
                </Statistic>
                <Card.Header>
                    {sensor.name}
                </Card.Header>
                <Card.Meta>
                    <br />
                    <p><Icon name='circle' /> ID: {sensor.id}</p>
                    <p><Icon name='home' /> Room: {sensor.room.room}</p>
                    <p><Icon name='circle' /> Floor: {sensor.room.floor}</p>
                    <p><Icon name='circle' /> Status: {sensor.status}</p>
                    <p><Icon name='circle' /> {sensor.uuid}</p>

                </Card.Meta>
            </Card.Content>
            <Card.Content extra>
                {switchableButton}
                <div className="ui two buttons">
                    <Link to={'/sensor/edit/' + sensor.id} className='ui basic button green'><Icon name='pencil' />Edit </Link>
                    <Button basic color="red" onClick={() => { deleteSensor(sensor) }}><Icon name='delete' />Delete</Button>
                </div>
            </Card.Content>
        </Card>
    )

    SensorCard.propTypes = {
        sensor: React.propTypes.object.isRequired
    }
}