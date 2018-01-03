import React from 'react';
import { Redirect } from 'react-router';
import { SubmissionError } from 'redux-form';
import { connect } from 'react-redux';
import { createRoom, updateRoom, fetchRoom } from '../../actions/RoomActions';
import RoomForm from '../../components/room/RoomForm';

class RoomFormPage extends React.Component {
  state = {
    redirect: false
  };

  componentDidMount() {
    const id = this.props.match.params.id;
    if (id) {
      this.props.fetchRoom(id);
    }
  }

  //todo polepsz
  submit = room => {
    if (!room.id) {
      return this.props
        .createRoom(room)
        .then(response => {
          this.setState({ redirect: true });
        })
        .catch(err => {
          throw new SubmissionError(this.props.errors);
        });
    } else {
      return this.props
        .updateRoom(room)
        .then(response => {
          this.setState({ redirect: true });
        })
        .catch(err => {
          throw new SubmissionError(this.props.errors);
        });
    }
  };

  render() {
    return (
      <div>
        {this.state.redirect ? (
          <Redirect to={'/room'} />
        ) : (
            <RoomForm
              room={this.props.room}
              loading={this.props.loading}
              onSubmit={this.submit}
            />
          )}
      </div>
    );
  }
}

function mapStateToProps(state) {
  return {
    room: state.roomStore.room,
    errors: state.roomStore.errors,
    loading: state.roomStore.loading
  };
}

export default connect(mapStateToProps, { createRoom, updateRoom, fetchRoom })(RoomFormPage);
