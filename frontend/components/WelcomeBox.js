/* eslint-disable react/jsx-max-depth */

import {createElement, Component, Fragment, Card, PreviewPanel, Paragraph, Text, Dialog, Button} from '@plesk/ui-library';
import WelcomeBoxContent from './WelcomeBoxContent';
import WelcomeBoxCss from '../containers/WelcomeBox/WelcomeBoxCss';
import WelcomeBoxHtml from '../containers/WelcomeBox/WelcomeBoxHtml';

class WelcomeBox extends Component {
    constructor(props)
    {
        super(props);

        this.state = {
            show: false
        }
    }

    render()
    {
        return (
            <Fragment>
                <WelcomeBoxCss/>
                <div id="welcome-box">
                    <Card
                        title={<WelcomeBoxHtml string={this.props.data.title}/>}
                        sideHeader={
                            <PreviewPanel image={this.props.data.image} padding={[5, 5]}/>
                        }
                        buttons={[
                            {
                                icon: 'clean',
                                onClick: () => this.setState({show: 1}),
                                tooltip: this.props.locale['tooltip.disable']
                            },
                        ]}
                        sideContent={
                            <Paragraph>
                                <Text>
                                    <WelcomeBoxHtml string={this.props.data.description}/>
                                </Text>
                            </Paragraph>
                        }
                    >
                        <WelcomeBoxContent {...this.props}/>
                    </Card>
                    <Dialog
                        isOpen={this.state.show === 1}
                        title={this.props.locale['dialog.disable.title']}
                        buttons={
                            <Button intent="primary" component="a" href="/modules/welcome/index.php/index/disable">{this.props.locale['dialog.disable.button']}</Button>}
                        size="md"
                        onClose={this.state.show = false}
                    >
                        {this.props.locale['dialog.disable.content']}
                    </Dialog>
                </div>
            </Fragment>
        )
    }
}

export default WelcomeBox;
