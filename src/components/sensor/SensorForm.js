import React from 'react';
import { Form, Grid, Button } from 'semantic-ui-react';
import { Field, reduxForm } from 'redux-form';
import classnames from 'classnames';

class SensorForm extends React.Component {
  renderField = ({ input, label, type, meta: { touched, error } }) => (
    <Form.Field className={classnames({ error: touched && error })}>
      <label>{label}</label>
      <input {...input} placeholder={label} type={type} />
      {touched && error && <span className="error">{error.message}</span>}
    </Form.Field>
  );

  componentWillReceiveProps = (nextProps) => {
      const { sensor } = nextProps;
      if (sensor.id !== this.props.sensor.id) {
          this.props.initialize(sensor);
      }
  }
  render() {
    const { handleSubmit, pristine, submitting, loading } = this.props;

    return (
      <Grid centered columns={2}>
        <Grid.Column>
          <h1 style={{ marginTop: '1em' }}>Edit sensor</h1>
          <Form onSubmit={handleSubmit} loading={loading}>
            <Field
              name="name"
              type="text"
              component={this.renderField}
              label="Set sensor name"
            />
            <Field
              name="room"
              type="text"
              component={this.renderField}
              label="Set sensor room"
            />
            <Button primary type="submit" disabled={pristine || submitting}>
              Save
            </Button>
          </Form>
        </Grid.Column>
      </Grid>
    );
  }
}

const validate = values => {
  const errors = {};
  if (!values.name) {
    errors.name = {
      message: 'You need to provide valid name'
    };
  }
  if (!values.room) {
    errors.room = {
      message: 'You need to provide a room'
    };
  }
  return errors;
};

export default reduxForm({ form: 'sensor', validate })(SensorForm);
