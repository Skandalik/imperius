import React from 'react';
import { Redirect } from 'react-router';
import { SubmissionError } from 'redux-form';
import { connect } from 'react-redux';
import {
  updateSensor,
  fetchSensor
} from '../../actions/SensorActions';
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
  }

  logConsole = variable => {
    return console.log(variable);
  };

  //todo polepsz
  submit = sensor => {
    if (!sensor.id) {
      return this.props
        .saveSensor(sensor)
        .then(response => {
          this.setState({ redirect: true });
        })
        .catch(err => {
          throw new SubmissionError(this.props.errors);
        });
    } else {
      return this.props
        .updateSensor(sensor)
        .then(response => {
          this.setState({ redirect: true });
        })
        .catch(err => {
          throw new SubmissionError(this.props.errors);
        });
    }
  };

  render() {
    return (
      <div>
        {this.state.redirect ? (
          <Redirect to={'/sensor'} />
        ) : (
          <SensorForm
            sensor={this.props.sensor}
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
    errors: state.sensorStore.errors,
    loading: state.sensorStore.loading
  };
}

export default connect(mapStateToProps, {
  updateSensor,
  fetchSensor
})(SensorFormPage);
