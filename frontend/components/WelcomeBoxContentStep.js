/* eslint-disable react/jsx-max-depth */

import {createElement, Component, Item, Grid, GridCol, Button, Icon, ContentLoader} from '@plesk/ui-library';
import axios from 'axios';

class WelcomeBoxContentStep extends Component {
    constructor(props)
    {
        super(props);
        this.state = props;

        this.indexGroup = props.indexGroup;
        this.index = props.index;

        this.state.response = undefined;
        this.state.completed = this.setCompletedStatus(Boolean(this.state.completed));
        this.state.completedIcon = this.setCompletedButtonImage(Boolean(this.state.completed));
    }

    componentDidMount()
    {
        axios.get('/modules/welcome/index.php/index/group?group=' + this.indexGroup + '&step=' + this.index)
            .then(({data}) => {
                data.response = true;
                data.completed = this.setCompletedStatus(Boolean(data.completed));
                data.completedIcon = this.setCompletedButtonImage(Boolean(data.completed));
                this.setState(data)
            })
    }

    setCompletedStatus = (completedStatus) => {
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
        if(!this.state.response)
        {
            return (
                <ContentLoader/>
            )
        }

        return (
            <div className={`${this.state.completed ? 'welcome-single-item completed' : 'welcome-single-item'}`}>
                <Grid xs={3} gap="xs">
                    <GridCol xs={9}>
                        <Item
                            icon={{src: this.state.image, size: '64'}}
                            title={this.state.title}
                        >
                            <div dangerouslySetInnerHTML={{__html: this.state.description}}/>
                        </Item>
                    </GridCol>
                    <GridCol xs={2}>
                        <div className="welcome-single-action-button">
                            {this.state.buttons.map(({...button}) => {
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
