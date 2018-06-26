/* eslint-disable react/jsx-max-depth */

import {createElement, Component, Text, Fragment, Paragraph, Tabs, Tab} from '@plesk/ui-library';
import WelcomeBoxContentStep from './WelcomeBoxContentStep';

class WelcomeBoxContent extends Component {
    constructor(props)
    {
        super(props);

        this.groups = props.data.groups;
        this.view = this.groups.length === 1 ? 'plain' : 'tabs';

        this.state = {};
    }

    setStepToggleStatus = (step, index) => {
        this.setState((step, index) => {
            return {index: !step.completed}
        });
    }

    renderOutputPlain = (group, indexGroup) => {
        return (
            <Fragment>
                <h2>{group.title}</h2>
                <div className="welcome-single-page">
                    {group.steps.map(({...step}, index) => {
                            return <WelcomeBoxContentStep {...step} indexGroup={indexGroup} index={index}/>
                        }
                    )}
                </div>
            </Fragment>
        );
    }

    renderOutputTab = (group, indexGroup) => {
        return (
            <Tab title={group.title}>
                <div className="welcome-single-page">
                    {group.steps.map(({...step}, index) => {
                            return <WelcomeBoxContentStep {...step} indexGroup={indexGroup} index={index}/>
                        }
                    )}
                </div>
            </Tab>
        );
    }

    renderOutputWrapper = () => {
        if(this.view === 'plain')
        {
            return this.renderOutputPlain(this.groups[0], 0)
        }

        return (
            <Tabs>
                {this.groups.map(({...group}, indexGroup) => {
                        return this.renderOutputTab(group, indexGroup)
                    }
                )}
            </Tabs>
        )
    }

    render()
    {
        return (
            <Fragment>
                {this.renderOutputWrapper()}
            </Fragment>
        )
    }
}

export default WelcomeBoxContent;
