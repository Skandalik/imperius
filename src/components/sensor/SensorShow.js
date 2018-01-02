import React from 'react';

class SensorShow extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      id: props.id,
      sensor: null,
      isLoading: true
    };
  }
  render() {
    this.fetchSensor();
    return (
      <div>
        <p>Id is: {this.state.id}</p>
        {this.isDataLoaded()}
      </div>
    );
  }

  isDataLoaded() {
    if (this.state.isLoading) {
      return <h2>Loading...</h2>;
    }

    return (
      <div>
        <p>{this.state.sensor.id}</p>
        <p>{this.state.sensor.uuid}</p>
        <p>{this.state.sensor.status}</p>
      </div>
    );
  }

  fetchSensor() {
    fetch('http://imperius.home:8090/api/sensors/' + this.state.id, {
      method: 'GET',
      headers: {
        Accept: 'application/json'
      }
    })
      .then(response => {
        return response.json();
      })
      .then(responseJson => {
        let sensor = responseJson;
        this.setState({
          sensor: responseJson,
          isLoading: false,
        });
        console.log('state', this.state.sensor);
      })
      .catch(error => {
        console.error(error);
      });
  }
}

export default SensorShow;
