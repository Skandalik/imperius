import React from 'react';
import { Grid, Segment, Dimmer, Loader } from 'semantic-ui-react';
import RoomCard from './RoomCard';

export default function RoomList({ rooms, loading, deleteRoom }) {
  const cards = () => {
    return rooms.map(room => {
      return (
        <Grid.Column key={room.id}>
          <RoomCard
            key={room.id}
            room={room}
            deleteRoom={deleteRoom}
          />
        </Grid.Column>
      );
    });
  };

  const minHeight = { minHeight: 200, width: 'auto', margin: 'auto 0' };
  return (
    <div>
      <Segment centered='true' style={minHeight}>
        <Dimmer inverted active={loading}>
          <Loader inverted content='Loading rooms...' />
        </Dimmer>
        <Grid relaxed container columns={3}>
          {cards()}
        </Grid>
      </Segment>
    </div>
  );
}
