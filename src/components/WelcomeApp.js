import { createElement, Tab, Tabs,  MenuItem, Label, Card, Menu, Button, PreviewPanel, Section, SectionItem, Translate } from '@plesk/ui-library';
import SecurityInformation from './SecurityInformation';
import WebsiteInformation from './WebsiteInformation';
import React from 'react';


const WelcomeApp = () => (
    
            <div>
        
        <Card
            title={<Translate content="WelcomeApp.title" />}
            menu={
                <Menu>
                    <MenuItem>{'Skip'}</MenuItem>
                    <MenuItem>{'Start Again'}</MenuItem>
                   
                </Menu>
            }
            sideHeader={
                <PreviewPanel image="https://github.com/plesk/ext-welcome-business/blob/master/htdocs/images/plesk_octopus_generic.png?raw=true">
                    <h1 style={{ color: '#fff' }}></h1>
                    <h4 style={{ color: '#fff' }}></h4>
                  
                </PreviewPanel>
            }
            sideContent={
                <Section>
                    <SectionItem title="Steps done">
                        <a>2/12</a>
                    </SectionItem>
                    <SectionItem title="Another Point">
                        <Label intent="success">OK</Label>
                    </SectionItem>
                </Section>
            }
            rowContent={'Row content'}
        >
        
            <Tabs active={1}>
                <Tab key={1} title="Information and tools">
                   <Translate content="WelcomeApp.lead" />
                </Tab>
                <Tab key={2} title="Security">
                    <SecurityInformation />
                </Tab>
                <Tab key={3} title="Website">
                    <WebsiteInformation />
                </Tab>
                <Tab key={4} title="SSL">
                    Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                </Tab>
                <Tab key={5} title="E-Mail">
                    Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                </Tab>
                <Tab key={6} title="Backup">
                    Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                </Tab>
            </Tabs>
        </Card>
            </div>
        );


    export default WelcomeApp;
