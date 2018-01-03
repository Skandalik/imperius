import { client } from './index';

const url = '/rooms';

export function fetchRooms() {
  return dispatch => {
    return dispatch({
      type: 'FETCH_ROOMS',
      payload: client.get(url)
    });
  };
}

export function fetchRoom(id) {
  return dispatch => {
    return dispatch({
      type: 'FETCH_ROOM',
      payload: client.get(`${url}/${id}`)
    });
  };
}

export function createRoom(room) {
  return dispatch => {
    return dispatch({
      type: 'CREATE_ROOM',
      payload: client.post(url, room)
    });
  };
}

export function updateRoom(room) {
  return dispatch => {
    return dispatch({
      type: 'UPDATE_ROOM',
      payload: client.put(`${url}/${room.id}`, room)
    });
  };
}

export function deleteRoom(room) {
  return dispatch => {
    return dispatch({
      type: 'DELETE_ROOM',
      meta: {
        roomObject: room
      },
      payload: client.delete(`${url}/${room.id}`)
    });
  };
}