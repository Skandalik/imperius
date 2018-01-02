import React from 'react';
import { Card, Button, Icon } from 'semantic-ui-react';
import { Link } from 'react-router-dom';

export default function SensorCard({sensor, editSensor, deleteSensor}) {
    return(
        <Card>
            <Card.Content>
                <Card.Header>
                    <Icon name='user outline' /> {sensor.id} {sensor.uuid}
                </Card.Header>
                <Card.Description>
                    <p><Icon name='phone'/> {sensor.ip} </p>
                    <p><Icon name='mail outline'/> {sensor.status} </p>
                </Card.Description>
            </Card.Content>
            <Card.Content extra>
            <div className="ui two buttons">
                <Link to={'/sensor/edit/' + sensor.id} className='ui basic button green'> Edit </Link>
                <Button basic color="red" onClick={() => {deleteSensor(sensor.id)}}>Delete</Button>
            </div>
            </Card.Content>
        </Card>
    )

    SensorCard.propTypes = {
        sensor: React.propTypes.object.isRequired
    }
}