import React from 'react';
import { Redirect } from 'react-router';
import { SubmissionError } from 'redux-form';
import { connect } from 'react-redux';
import { updateSensor, fetchSensor } from '../../actions/SensorActions';
import { fetchRooms } from '../../actions/RoomActions';
import SensorForm from '../../components/sensor/SensorForm';

class SensorFormPage extends React.Component {
  state = {
    redirect: false
  };

  componentDidMount() {
    const id = this.props.match.params.id;
    if (id) {
      this.props.fetchSensor(id);
    }

    this.props.fetchRooms();
  }

  //todo polepsz
  submit = sensor => {
    return this.props
      .updateSensor(sensor)
      .then(response => {
        this.setState({ redirect: true });
      })
      .catch(err => {
        throw new SubmissionError(this.props.errors);
      });
  };

  render() {
    return (
      <div>
        {this.state.redirect ? (
          <Redirect to={'/sensor'} />
        ) : (
            <SensorForm
              sensor={this.props.sensor}
              rooms={this.props.rooms}
              loading={this.props.loading}
              onSubmit={this.submit}
            />
          )}
      </div>
    );
  }
}

function mapStateToProps(state) {
  return {
    sensor: state.sensorStore.sensor,
    rooms: state.roomStore.rooms,
    errors: state.sensorStore.errors,
    loading: state.sensorStore.loading
  };
}

export default connect(mapStateToProps, { updateSensor, fetchSensor, fetchRooms })(SensorFormPage);
