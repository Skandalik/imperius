import React from 'react';
import { Form, Grid, Button, Icon } from 'semantic-ui-react';
import { Field, reduxForm } from 'redux-form';
import classnames from 'classnames';
import { Link } from 'react-router-dom';

class RoomForm extends React.Component {
  renderField = ({ input, label, type, meta: { touched, error } }) => (
    <Form.Field className={classnames({ error: touched && error })}>
      <label>{label}</label>
      <input {...input} placeholder={label} type={type} />
      {touched && error && <span className="error">{error.message}</span>}
    </Form.Field>
  );

  componentWillReceiveProps = (nextProps) => {
      const { room } = nextProps;

    console.log(room);
      if (room.id !== this.props.room.id) {
          this.props.initialize(room);
          
      }
  }

  render() {
    const parse = value => value === undefined ? undefined : parseInt(value)
    const { handleSubmit, pristine, submitting, loading } = this.props;
    const padding = {padding: 50};
    return (
      <Grid centered columns={2} >
        <Grid.Column>
          <h1 style={{ marginTop: '1em' }}>Edit room</h1>
          <Form onSubmit={handleSubmit} loading={loading} style={padding}>
            <Field
              name="room"
              type="text"
              component={this.renderField}
              label="Set room name"
            />
            <Field
              name="floor"
              type="number"
              parse={parse}
              component={this.renderField}
              label="Set floor"
            />
            <Button primary type="submit" disabled={pristine || submitting}>
              Save
            </Button>
            <Link to={'/room'} className='ui button'><Icon name='arrow left'/>Go back </Link>
          </Form>
        </Grid.Column>
      </Grid>
    );
  }
}

const validate = values => {
  const errors = {};
  if (!values.room) {
    errors.room = {
      message: 'You need to provide valid room name!'
    };
  }
  if (!values.floor) {
    errors.floor = {
      message: 'You need to provide a floor'
    };
  }
  return errors;
};

export default reduxForm({ form: 'room', validate })(RoomForm);
