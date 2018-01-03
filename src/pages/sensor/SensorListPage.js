import React from 'react';
import SensorList from '../../components/sensor/SensorList';
import { connect } from 'react-redux';
import { fetchSensors, deleteSensor, refetchSensors, checkStatusSensor, setStatusSensor } from '../../actions/SensorActions';
import ReactInterval from 'react-interval';

class SensorListPage extends React.Component {
  componentDidMount() {
    this.props.fetchSensors();
  }

  render() {
    return (
      <div>
        <ReactInterval
          timeout={5000}
          enabled={true}
          callback={() => this.props.refetchSensors()}
        />

        <h2>Manage your sensors</h2>
        <SensorList
          sensors={this.props.sensors}
          loading={this.props.loading}
          deleteSensor={this.props.deleteSensor}
          checkStatusSensor ={this.props.checkStatusSensor}
          setStatusSensor = {this.props.setStatusSensor}
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

export default connect(mapStateToProps, { refetchSensors, fetchSensors, deleteSensor, checkStatusSensor, setStatusSensor })(SensorListPage);
