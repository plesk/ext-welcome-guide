/* eslint-disable react/jsx-max-depth */

import {createElement, Component, Text, Fragment, Paragraph, Tabs, Tab} from '@plesk/ui-library';
import WelcomeBoxContentStep from './WelcomeBoxContentStep';
import WelcomeBoxHtml from '../containers/WelcomeBox/WelcomeBoxHtml';

class WelcomeBoxContent extends Component {
    constructor(props)
    {
        super(props);

        this.groups = props.data.groups;
        this.view = this.groups.length === 1 ? 'plain' : 'tabs';
    }

    renderOutputPlain = (group, indexGroup) => {
        return (
            <Fragment>
                <h2>
                    {<WelcomeBoxHtml string={group.title}/>}
                </h2>
                <div className="welcome-single-page">
                    {group.steps.map(({...step}, index) => {
                            return <WelcomeBoxContentStep {...step} indexGroup={indexGroup} index={index} locale={this.props.locale} canInstall={this.props.canInstall}/>
                        }
                    )}
                </div>
            </Fragment>
        );
    }

    renderOutputTab = (group, indexGroup) => {
        return (
            <Tab title={<WelcomeBoxHtml string={group.title}/>}>
                <div className="welcome-single-page">
                    {group.steps.map(({...step}, index) => {
                            return <WelcomeBoxContentStep {...step} indexGroup={indexGroup} index={index} locale={this.props.locale} canInstall={this.props.canInstall}/>
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
