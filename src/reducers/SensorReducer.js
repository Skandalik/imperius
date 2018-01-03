const defaultState = {
  sensors: [],
  sensor: {},
  loading: false,
  errors: {}
};

// TODO popraw refetching sensorów

export default (state = defaultState, action = {}) => {
  switch (action.type) {
    case 'REFETCH_SENSORS_FULFILLED': {
      return {
        ...state,
        loading: false,
        sensors: action.payload.data.data || action.payload.data
      };
    }
    case 'FETCH_SENSORS_PENDING': {
      return {
        ...state,
        loading: true,
        sensors: state.sensors
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
        sensor: {}
      };
    }
    case 'FETCH_SENSOR_FULFILLED': {
      return {
        ...state,
        sensor: action.payload.data,
        errors: {}
      };
    }
    case 'CHECK_STATUS_PENDING': {
      let sensor = action.meta.sensorObject
      sensor.loading = true
      return {
        ...state,
        sensors: state.sensors.map(
          item => (item.id === sensor.id ? sensor : item)
        ),
      };
    }
    case 'CHECK_STATUS_FULFILLED': {
      let sensor = action.payload.action.payload.data
      sensor.loading = false
      return {
        ...state,
        sensors: state.sensors.map(
          item => (item.id === sensor.id ? sensor : item)
        ),
      };
    }
    case 'SET_STATUS_PENDING': {
      let sensor = action.meta.sensorObject
      sensor.loading = true
      return {
        ...state,
        sensors: state.sensors.map(
          item => (item.id === sensor.id ? sensor : item)
        ),
      };
    }
    case 'SET_STATUS_FULFILLED': {
      let sensor = action.payload.data
      sensor.loading = false
      return {
        ...state,
        sensors: state.sensors.map(
          item => (item.id === sensor.id ? sensor : item)
        ),
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
      let sensor = action.meta.sensorObject
      sensor.loading = true
      return {
        ...state,
        sensors: state.sensors.map(
          item => (item.id === sensor.id ? sensor : item)
        ),
      };
    }
    case 'DELETE_SENSOR_FULFILLED': {
      const id = action.meta.sensorObject.id
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
