import React from 'react';
import { Switch, Route } from 'react-router-dom';
import RoomListPage from '../../pages/room/RoomListPage';
import RoomFormPage from '../../pages/room/RoomFormPage';
class RoomRoutes extends React.Component {
  render() {
    return (
      <div>
        <Switch>
          <Route path="/room" component={RoomListPage} exact key="room_list" />
          <Route path="/room/create" component={RoomFormPage} key="room_create" />
          <Route path="/room/edit/:id" component={RoomFormPage} key="room_edit" />
        </Switch>
      </div>
    );
  }
}

export default RoomRoutes;
