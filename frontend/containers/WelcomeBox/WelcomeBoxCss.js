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
                padding: 10px 15px;
            }

            #welcome-box .welcome-single-item.completed .pul-item__icon .pul-icon
            {
                width: 32px;
                height: 32px;
            }

            #welcome-box .welcome-single-item.completed .pul-item__body
            {
                display:none
            }

            #welcome-box .welcome-single-item.completed .pul-button--primary
            {
                display:none
            }

            #welcome-box .welcome-single-item .button-toggle-status
            {
                pointer-events: auto;
                float: right;
            }

            #welcome-box .welcome-single-item .pul-item__content > span {
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

            #welcome-box .welcome-single-action-button {
                text-align: center;
            }

            #welcome-box .welcome-single-action-button .pul-button--primary:first-child {
                margin: 0 0 2px 0;
            }

            #welcome-box .welcome-single-action-button .pul-button--primary {
                margin: 0 0 2px 2px;
            }
        `}
    </style>
);

export default WelcomeBoxCss;
