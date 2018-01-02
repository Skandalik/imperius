import { combineReducers } from 'redux';
import { reducer as formReducer } from 'redux-form';
import SensorReducer from './SensorReducer';

const reducers = {
    sensorStore: SensorReducer,
    form: formReducer
};

const rootReducer = combineReducers(reducers);

export default rootReducer;