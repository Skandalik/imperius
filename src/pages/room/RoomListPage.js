import React from 'react';
import { connect } from 'react-redux';
import RoomList from '../../components/room/RoomList';
import { fetchRooms, deleteRoom } from '../../actions/RoomActions';
import Link from 'react-router-dom/Link';
import Icon from 'semantic-ui-react/dist/commonjs/elements/Icon/Icon';

class RoomListPage extends React.Component {
  componentDidMount() {
    this.props.fetchRooms();
  }

  render() {
    return (
      <div>
        <h2>Manage your rooms</h2>
        <Link to={'/room/create'} className='primary ui button'><Icon className='plus'/>Add new Room </Link>
        <br />
        <br />
        <RoomList
          rooms={this.props.rooms}
          loading={this.props.loading}
          deleteRoom={this.props.deleteRoom}
        />
      </div>
    );
  }
}

function mapStateToProps(state) {
  return {
    rooms: state.roomStore.rooms,
    loading: state.roomStore.loading
  };
}

export default connect(mapStateToProps, { fetchRooms, deleteRoom })(RoomListPage);