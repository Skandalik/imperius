import React, { Component } from 'react';
import { Switch, Route } from 'react-router-dom';
import SensorRoutes from '../routes/sensor/SensorRoutes';
import RoomRoutes from '../routes/room/RoomRoutes';

class Main extends Component {
  render() {
    return (
      <div className="container">
        <Switch>
          <Route path="/sensor" component={SensorRoutes} key="sensor" />
          <Route path="/room" component={RoomRoutes} key="room" />
          <Route path="/logout" exact={true} key="logout" />
        </Switch>
      </div>
    );
  }
}

export default Main;