import { createElement, LocaleProvider, PropTypes } from '@plesk/ui-library';
import WelcomeBox from '../containers/WelcomeBox';

const App = ({ locales, ...props }) => (
    <LocaleProvider messages={locales}>
        <WelcomeBox {...props}/>
    </LocaleProvider>
);

export default App;
