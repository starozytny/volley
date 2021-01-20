import React, { Component } from 'react';

import Routing           from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import { Page }          from "@dashboardComponents/Layout/Page";
import { LoaderElement } from "@dashboardComponents/Layout/Loader";
import Sort              from "@dashboardComponents/functions/sort";
import Formulaire        from "@dashboardComponents/functions/Formulaire";

function getBase64(file, self) {
    var reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = function () {
        self.setState({logo: {value: reader.result} })
    };
    reader.onerror = function (error) {
        console.log('Error: ', error);
    };
}

export class Settings extends Component {
    constructor(props) {
        super();

        this.state = {
            context: "list",
            loadPageError: false,
            loadData: true,
            data: null
        }
    }

    componentDidMount = () => {
        Formulaire.axiosGetData(this, Routing.generate('api_settings_index'))
    }

    render () {
        const { loadPageError, context, loadData, data } = this.state;

        console.log(data)

        let content = null, havePagination = false;
        switch (context){
            default:
                content = loadData ? <LoaderElement /> : <div>Hello</div>
                break;
        }

        return <>
            <Page ref={this.page} haveLoadPageError={loadPageError} havePagination={havePagination}>
                {content}
            </Page>
        </>
    }
}