import React from 'react';
import { Card, Button, Icon, Dimmer, Loader } from 'semantic-ui-react';
import { Link } from 'react-router-dom';

export default function RoomCard({ room, deleteRoom }) {
    return (
        <Card>
            <Dimmer invert='true' active={room.loading}>
                <Loader content='Loading...' />
            </Dimmer>
            <Card.Content>
                <Card.Header>
                    {room.room}
                </Card.Header>
                <Card.Meta>
                    <br />
                    <p><Icon name='home' /> Floor: {room.floor}</p>
                </Card.Meta>
            </Card.Content>
            <Card.Content extra>
                <div className="ui two buttons">
                    <Link to={'/room/edit/' + room.id} className='ui basic button green'><Icon name='pencil' />Edit </Link>
                    <Button basic color="red" onClick={() => { deleteRoom(room) }}><Icon name='delete' />Delete</Button>
                </div>
            </Card.Content>
        </Card>
    )

    RoomCard.propTypes = {
        room: React.propTypes.object.isRequired
    }
}