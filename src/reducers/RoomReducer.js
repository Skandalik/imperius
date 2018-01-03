const defaultState = {
  rooms: [],
  room: {},
  loading: false,
  errors: {}
};

export default (state = defaultState, action = {}) => {
  switch (action.type) {
    case 'FETCH_ROOMS_PENDING': {
      return {
        ...state,
        loading: true,
        rooms: state.rooms
      };
    }
    case 'FETCH_ROOMS_FULFILLED': {
      return {
        ...state,
        loading: false,
        rooms: action.payload.data.data || action.payload.data
      };
    }
    case 'FETCH_ROOM_PENDING': {
      return {
        ...state,
        room: {}
      };
    }
    case 'FETCH_ROOM_FULFILLED': {
      return {
        ...state,
        room: action.payload.data,
        errors: {}
      };
    }
    case 'CREATE_ROOM_PENDING': {
      return {
        ...state,
        loading: true
      };
    }
    case 'CREATE_ROOM_FULFILLED': {
      const room = action.payload.data;
      return {
        ...state,
        loading: true,
        rooms: [...state.rooms, action.payload.data],
        errors: {},
        loading: false
      };
    }
    case 'UPDATE_ROOM_PENDING': {
      return {
        ...state,
        loading: true
      };
    }
    case 'UPDATE_ROOM_FULFILLED': {
      const room = action.payload.data;
      return {
        ...state,
        rooms: state.rooms.map(
          item => (item.id === room.id ? room : item)
        ),
        errors: {},
        loading: false
      };
    }
    case 'DELETE_ROOM_PENDING': {
      let room = action.meta.roomObject
      room.loading = true
      return {
        ...state,
        rooms: state.rooms.map(
          item => (item.id === room.id ? room : item)
        ),
      };
    }
    case 'DELETE_ROOM_FULFILLED': {
      const id = action.meta.roomObject.id
      return {
        ...state,
        loading: false,
        rooms: state.rooms.filter(item => item.id !== id)
      };
    }
    default:
      return state;
  }
};
