/* eslint-disable react/jsx-max-depth */

import {createElement, Component, Item, Grid, GridCol, Button, Icon} from '@plesk/ui-library';
import axios from 'axios';

class WelcomeBoxContentStep extends Component {
    constructor(props)
    {
        super(props);

        this.step = props;

        // TODO Indexes are required to store the completed state - add function with AJAX request
        this.indexGroup = props.indexGroup;
        this.index = props.index;

        this.state = {completed: this.setCompletedStatus(Boolean(this.step.completed))};
        this.state = {completedIcon: this.setCompletedButtonImage(Boolean(this.state.completed))};
    }

    setCompletedStatus = (completedStatus) => {
        if(typeof this.state !== 'undefined')
        {
            if(typeof this.state.completed !== 'undefined')
            {
                return this.state.completed
            }
        }

        return (completedStatus === true)
    }

    setCompletedButtonImage = (completedStatus) => {
        if(completedStatus === true)
        {
            return 'check-mark'
        }

        return ''
    }

    setStepToggleStatus = () => {
        this.setState({completed: !this.state.completed});
        this.setState({completedIcon: this.setCompletedButtonImage(!this.state.completed)});
        axios.get('/modules/welcome/index.php/index/progress?group=' + this.indexGroup + '&step=' + this.index);
    }

    setToggleButtonIntent = () => {
        if(this.state.completed)
        {
            return 'success'
        }

        return 'secondary'
    }

    render()
    {
        return (
            <div className={`${this.state.completed ? 'welcome-single-item completed' : 'welcome-single-item'}`}>
                <Grid xs={3} gap="xs">
                    <GridCol xs={9}>
                        <Item
                            icon={{src: this.step.image, size: '64'}}
                            title={this.step.title}
                        >
                            <div dangerouslySetInnerHTML={{__html: this.step.description}}/>
                        </Item>
                    </GridCol>
                    <GridCol xs={2}>
                        <div className="welcome-single-action-button">
                            {this.step.buttons.map(({...button}) => {
                                    return <Button component="a" href={button.url} intent="primary">{button.title}</Button>
                                }
                            )}
                        </div>
                    </GridCol>
                    <GridCol xs={1}>
                        <div className="button-toggle-status">
                            <Button onClick={() => this.setStepToggleStatus()} intent={this.setToggleButtonIntent()}>
                                <Icon name={this.state.completedIcon} size="16"/>
                            </Button>
                        </div>
                    </GridCol>
                </Grid>
            </div>
        )
    }
}

export default WelcomeBoxContentStep;
