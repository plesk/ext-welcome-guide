/* eslint-disable react/jsx-max-depth */
import {createElement} from '@plesk/ui-library';

const WelcomeBoxCss = () => (
    <style>
        {`
            #welcome-box {
                margin-bottom: 20px;
            }

            #welcome-box .welcome-single-page > .welcome-single-item:first-child {
                border-top: 1px solid #DCDCDC;
            }

            #welcome-box .welcome-single-item {
                padding: 20px 10px;
                border-bottom: 1px solid #DCDCDC;
            }

            #welcome-box .welcome-single-item.completed
            {
                pointer-events: none;
                opacity: 0.4;
                background: #EAEAEA;
            }

            #welcome-box .welcome-single-item .button-toggle-status
            {
                pointer-events: auto;
                float: right;
            }

            #welcome-box .welcome-single-item .pul-item__title {
                font-weight: bold;
                margin-bottom: 4px;
                display: inline-block;
                font-size: 14px;
            }

            #welcome-box .pul-card__side {
                max-width: 300px;
            }

            #welcome-box .pul-button--secondary:hover {
                background-image: url('/modules/welcome/images/buttonToggleStatusHover.png');
                background-repeat: round;
            }
        `}
    </style>
);

export default WelcomeBoxCss;
