/* eslint-disable react/jsx-max-depth */

import {createElement, Fragment, Card, PreviewPanel, Paragraph, Text} from '@plesk/ui-library';
import WelcomeBoxContent from '../../components/WelcomeBoxContent';
import WelcomeBoxCss from './WelcomeBoxCss';
import WelcomeBoxHtml from './WelcomeBoxHtml';

const WelcomeBox = ({...props}) => (
    <Fragment>
        <WelcomeBoxCss/>
        <div id="welcome-box">
            <Card
                title={<WelcomeBoxHtml string={props.data.title}/>}
                sideHeader={
                    <PreviewPanel image={props.data.image} padding={[5, 5]}/>
                }
                buttons={[
                    {
                        icon: 'clean',
                        onClick: () => window.open('/modules/welcome/index.php/index/disable', '_self'),
                        tooltip: props.locale['tooltip.disable.box']
                    },
                ]}
                sideContent={
                    <Paragraph>
                        <Text>
                            <WelcomeBoxHtml string={props.data.description}/>
                        </Text>
                    </Paragraph>
                }
            >
                <WelcomeBoxContent {...props}/>
            </Card>
        </div>
    </Fragment>
);

export default WelcomeBox;
