import { createElement, Tab, Tabs,Panel, ItemList, Item, Grid, MenuItem, Label, GridCol, Component, Icon, LocaleProvider, Translate, Card, Menu, Button, PreviewPanel, Section, SectionItem, render } from '@plesk/ui-library';
import { ThemeProvider } from 'styled-components';
import { description } from '../package';
import WelcomeApp from './components/WelcomeApp';
import React from 'react';

const App = ({ locale }) => (
    
            <div>
    <style>
        {`
            .Grid_demoBox {
                
                padding: 8px;
                padding-top: 15px;
                margin: 9 px;
                color: rgba(0,0,0,.3);
                box-shadow: 0 1px 2px rgba(0,0,0,.5);
            }
        `}
    </style>

    <Grid gap="xs">
        
        <GridCol >
            <div className="Grid_demoBox">
            <LocaleProvider messages={require(`./locale/${locale}.json`)}>
                <WelcomeApp />
            </LocaleProvider>
            </div>
        </GridCol>
        
    </Grid>
</div>
            
);




export default App;
