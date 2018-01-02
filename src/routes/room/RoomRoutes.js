import React from 'react';
import { Switch, Route } from 'react-router-dom';
import RoomListPage from '../../pages/room/RoomListPage';
class RoomRoutes extends React.Component {
  render() {
    return (
      <div>
        <Switch>
          <Route path="/room" component={RoomListPage} exact key="room_list" />
        </Switch>
      </div>
    );
  }
}

export default RoomRoutes;
