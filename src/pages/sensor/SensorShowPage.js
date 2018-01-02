import React from 'react';
import SensorShow from '../../components/sensor/SensorShow';

class SensorShowPage extends React.Component {
  render() {
    return (
      <div>
        <h2>Sensor</h2>
        <SensorShow id={this.props.match.params.id}/>
      </div>
    );
  }
}

export default SensorShowPage;
