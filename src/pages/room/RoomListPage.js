import React from 'react';
import RoomList from '../../components/room/RoomList';

class RoomListPage extends React.Component {
  render() {
    return (
      <div>
        <h2>Manage your rooms</h2>
        <RoomList/>
      </div>
    );
  }
}

export default RoomListPage;
