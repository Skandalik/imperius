import { client } from './index';

const url = '/sensors';

export function fetchSensors() {
  return dispatch => {
    return dispatch({
      type: 'FETCH_SENSORS',
      payload: client.get(url)
    });
  };
}

export function fetchSensorsAsync() {
  return dispatch => {
    setTimeout(()=> {
    return dispatch({
      type: 'FETCH_SENSORS',
      payload: client.get(url)
    }),1000
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

export function deleteSensor(id) {
  return dispatch => {
    return dispatch({
      type: 'DELETE_SENSOR',
      payload: client.delete(`${url}/${id}`)
    });
  };
}