/* eslint-disable react/jsx-max-depth */

import {Paragraph, Text, ItemList, Item, Button, CardList, Card, ToolbarGroup, createElement, Fragment, Carousel, Grid, GridCol, Panel, Media, MediaSection, Icon, Translate} from '@plesk/ui-library';

const WelcomeBox = ({...props}) => (
    <Fragment>
        <div id="welcome-box">
            <Paragraph>
                <Text>Plesk Welcome Extension</Text>
            </Paragraph>
        </div>
    </Fragment>
);

export default WelcomeBox;
