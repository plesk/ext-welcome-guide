/* eslint-disable react/jsx-max-depth */
import {createElement} from '@plesk/ui-library';

const WelcomeBoxHtml = ({string}) => {
    return (
        <span dangerouslySetInnerHTML={{__html: string}}/>
    )
}

export default WelcomeBoxHtml;
