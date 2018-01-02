import React from 'react';
import SensorCard from './SensorCard';
import Card from 'semantic-ui-react/dist/commonjs/views/Card/Card';

export default function SensorList({ sensors, loading, deleteSensor }) {
  const cards = () => {
    return sensors.map(sensor => {
      return (
        <SensorCard
          key={sensor.id}
          sensor={sensor}
          deleteSensor={deleteSensor}
        />
      );
    });
  };

  const loadingSensors = loading => {
    if (loading) {
      return (
        <div>
          <div className="ui segment">
            <p />
            <div className="ui active dimmer">
              <div className="ui loader" />
            </div>
          </div>
          <h2>Loading sensors...</h2>
        </div>
      );
    }
    return;
  };

  return (
    <div>
      {loadingSensors(loading)}
      <Card.Group>{cards()}</Card.Group>
    </div>
  );
}
