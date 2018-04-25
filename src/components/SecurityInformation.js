import { createElement, Panel, ItemList, Item, Component, render } from '@plesk/ui-library';
import React from 'react';


class SecurityInformation extends Component {
    render(){
        return(
            <div>
                <Panel>
                    <ItemList>
                        <Item
                            icon="http://placehold.it/64"
                            title="Activate Premium Antivirus "
                        />
                        <Item
                            icon="http://placehold.it/64"
                            title="Install Opsani"
                        />
                    </ItemList>
                </Panel>
            </div>
        );
    }
}

export default SecurityInformation;
