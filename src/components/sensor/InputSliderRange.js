import React from 'react';
import Button from 'semantic-ui-react/dist/commonjs/elements/Button/Button';
import Grid from 'semantic-ui-react/dist/commonjs/collections/Grid/Grid';
import GridRow from 'semantic-ui-react/dist/commonjs/collections/Grid/GridRow';
import GridColumn from 'semantic-ui-react/dist/commonjs/collections/Grid/GridColumn';
import Statistic from 'semantic-ui-react/dist/commonjs/views/Statistic/Statistic';

class InputSliderRange extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            sensor: this.props.sensor,
            min: this.props.min,
            max: this.props.max,
            current: this.props.current,
            id: this.props.id,
            step: this.props.step
        }
    }

    onChangeSlider = (event) => {
        this.setState({
            current: event.target.value
        })
    }

    onChangeFinished = () => {
        this.props.setStatusSensor(this.state.sensor, this.state.current)
    }

    turnOffButton = () => {
        if (this.state.current !== 0) {
            return <div>
                <Button circular icon='power' color='red' onClick={() => { this.props.setStatusSensor(this.state.sensor, 0) }}></Button>
            </div>
        }

        return '';
    }

    render() {
        return (
            <Grid>
                <GridRow>
                    <GridColumn width={1}>
                    </GridColumn>
                    <GridColumn width={1}>
                        <Statistic size='tiny'>
                            <Statistic.Value>{this.state.current}</Statistic.Value>
                            <Statistic.Label>Status</Statistic.Label>
                        </Statistic>
                    </GridColumn>

                    <GridColumn width={2}>
                    </GridColumn>
                    <GridColumn width={6}>
                        <input
                            type="range"
                            step={this.state.step}
                            min={this.state.min}
                            max={this.state.max}
                            id={this.state.id}
                            value={this.state.current}
                            className="input-slider slider"
                            onChange={this.onChangeSlider}
                            onMouseUp={this.onChangeFinished}
                            onTouchEnd={this.onChangeFinished}
                        />
                    </GridColumn>

                    <GridColumn width={2}>
                        {this.turnOffButton()}
                    </GridColumn>
                    <GridColumn width={2}>
                    </GridColumn>
                </GridRow>
            </Grid>
        )
    }
}


export default InputSliderRange;