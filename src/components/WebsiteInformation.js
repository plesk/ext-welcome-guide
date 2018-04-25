import { createElement, Panel, ItemList, Item, Component, render } from '@plesk/ui-library';
import React from 'react';


    
const WebsiteInformation = () => (    
    
            <div>
                    <Panel>
                    <ItemList>
                      <Item
                            icon="http://placehold.it/64"
                            title={<a href='/modules/wp-toolkit/index.php'>Get Started with WordPress </a>}
                             />
                        <Item
                            icon="http://placehold.it/64"
                            title="Activate HTTP2"
                        />
                    </ItemList>
                </Panel>
            </div>
) 
    

export default WebsiteInformation;
