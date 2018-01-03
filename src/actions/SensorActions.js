import { client } from './index';

const url = '/sensors';

export function refetchSensors() {
  return dispatch => {
    return dispatch({
      type: 'REFETCH_SENSORS',
      payload: client.get(url)
    });
  };
}

export function fetchSensors() {
  return dispatch => {
    return dispatch({
      type: 'FETCH_SENSORS',
      payload: client.get(url)
    });
  };
}

export function checkStatusSensor(sensor) {
  return dispatch => {
    return dispatch({
      type: 'CHECK_STATUS',
      meta: {
        sensorObject: sensor
      },
      payload: client.get(`${url}/${sensor.id}/status/check`).then(function (response) {
        return dispatch(fetchSensor(sensor.id))
      })
  });
  };
}

export function setStatusSensor(sensor, status) {
  return dispatch => {
    return dispatch({
      type: 'SET_STATUS',
      meta: {
        sensorObject: sensor
      },
      payload: client.put(`${url}/${sensor.id}/status/set/${status}`)
  });
  };
}


export function fetchSensor(id) {
  return dispatch => {
    return dispatch({
      type: 'FETCH_SENSOR',
      payload: client.get(`${url}/${id}`)
    });
  };
}

export function updateSensor(sensor) {
  return dispatch => {
    return dispatch({
      type: 'UPDATE_SENSOR',
      payload: client.put(`${url}/${sensor.id}`, sensor)
    });
  };
}

export function deleteSensor(sensor) {
  return dispatch => {
    return dispatch({
      type: 'DELETE_SENSOR',
      meta: {
        sensorObject: sensor
      },
      payload: client.delete(`${url}/${sensor.id}`)
    });
  };
}