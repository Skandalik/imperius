import React from 'react';
import SensorEdit from '../../components/sensor/SensorEdit';

class SensorEditPage extends React.Component {
  render() {
    return (
      <div>
        <h2>Edit sensor</h2>
        <SensorEdit id={this.props.match.params.id}/>
      </div>
    );
  }
}

export default SensorEditPage;
