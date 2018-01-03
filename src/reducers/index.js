import { combineReducers } from 'redux';
import { reducer as formReducer } from 'redux-form';
import SensorReducer from './SensorReducer';
import RoomReducer from './RoomReducer';

const reducers = {
    sensorStore: SensorReducer,
    roomStore: RoomReducer,
    form: formReducer
};

const rootReducer = combineReducers(reducers);

export default rootReducer;