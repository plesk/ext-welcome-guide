/* eslint-disable react/jsx-max-depth */

import {createElement, Fragment, Card, PreviewPanel, Paragraph, Text} from '@plesk/ui-library';
import WelcomeBoxContent from './WelcomeBoxContent';
import WelcomeBoxCss from './WelcomeBoxCss';

const WelcomeBox = ({...props}) => (
    <Fragment>
        <WelcomeBoxCss/>
        <div id="welcome-box">
            <Card
                title={props.data.title}
                sideHeader={
                    <PreviewPanel image={props.data.image}></PreviewPanel>
                }
                sideContent={
                    <Paragraph>
                        <Text>
                            <div dangerouslySetInnerHTML={{__html: props.data.description}}/>
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
