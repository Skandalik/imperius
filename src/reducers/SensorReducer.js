const defaultState = {
  sensors: [],
  sensor: {},
  loading: false,
  errors: {}
};

export default (state = defaultState, action = {}) => {
  switch (action.type) {
    case 'FETCH_SENSORS_PENDING': {
      return {
        ...state,
        loading: true,
        sensors: []
      };
    }
    case 'FETCH_SENSORS_FULFILLED': {
      return {
        ...state,
        loading: false,
        sensors: action.payload.data.data || action.payload.data
      };
    }
    case 'FETCH_SENSOR_PENDING': {
      return {
        ...state,
        loading: true,
        sensor: {}
      };
    }
    case 'FETCH_SENSOR_FULFILLED': {
      return {
        ...state,
        sensor: action.payload.data,
        loading: false,
        errors: {}
      };
    }
    case 'UPDATE_SENSOR_PENDING': {
      return {
        ...state,
        loading: true
      };
    }
    case 'UPDATE_SENSOR_FULFILLED': {
      const sensor = action.payload.data;
      return {
        ...state,
        sensors: state.sensors.map(
          item => (item.id === sensor.id ? sensor : item)
        ),
        errors: {},
        loading: false
      };
    }
    case 'UPDATE_SENSOR_REJECTED': {
      const data = action.payload.response.data;
      const { name, room } = data.errors;
      const errors = { global: data.message, name, room };
      return {
        ...state,
        errors: errors,
        loading: false
      };
    }
    case 'DELETE_SENSOR_PENDING': {
      return {
        ...state,
        loading: true
      };
    }
    case 'DELETE_SENSOR_FULFILLED': {
      const splittedUrl = action.payload.config.url.split('/');
      const id = parseInt(splittedUrl[splittedUrl.length - 1]);
      return {
        ...state,
        loading: false,
        sensors: state.sensors.filter(item => item.id !== id)
      };
    }
    default:
      return state;
  }
};
