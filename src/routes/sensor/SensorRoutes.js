import React from 'react';
import { Switch, Route } from 'react-router-dom';
import SensorListPage from '../../pages/sensor/SensorListPage';
import SensorShowPage from '../../pages/sensor/SensorShowPage';
import SensorFormPage from "../../pages/sensor/SensorFormPage";
class SensorRoutes extends React.Component {
  render() {
    return (
      <div>
        <Switch>
          <Route path="/sensor" component={SensorListPage} exact key="sensor_list" />
          <Route path="/sensor/show/:id" component={SensorShowPage} key="sensor_show" />
          <Route path="/sensor/edit/:id" component={SensorFormPage} key="sensor_edit" />
        </Switch>
      </div>
    );
  }
}

export default SensorRoutes;
