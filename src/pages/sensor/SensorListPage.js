import React from 'react';
import SensorList from '../../components/sensor/SensorList';
import { connect } from 'react-redux';
import { fetchSensors, deleteSensor, fetchSensorsAsync } from '../../actions/SensorActions';
import timer from 'react-timer-mixin';

class SensorListPage extends React.Component {
  componentDidMount() {
    this.props.fetchSensors();
  }

  render() {
    return (
      <div>
        <h2>Manage your sensors</h2>
        <SensorList
          sensors={this.props.sensors}
          loading={this.props.loading}
          deleteSensor={this.props.deleteSensor}
        />
      </div>
    );
  }
}

function mapStateToProps(state) {
  return {
    sensors: state.sensorStore.sensors,
    loading: state.sensorStore.loading
  };
}

export default connect(mapStateToProps, { fetchSensors, deleteSensor })(SensorListPage);
