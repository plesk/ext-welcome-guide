import {createElement} from '@plesk/ui-library';
import WelcomeBox from '../containers/WelcomeBox';

const App = ({locales, ...props}) => (
    <WelcomeBox {...props}/>
);

export default App;
