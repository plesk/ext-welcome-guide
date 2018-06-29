import {createElement} from '@plesk/ui-library';
import WelcomeBox from './WelcomeBox';

const App = ({locales, ...props}) => (
    <WelcomeBox {...props}/>
);

export default App;
