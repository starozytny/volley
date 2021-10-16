import React, { Component } from 'react';

import Routing           from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import { Layout }         from "@dashboardComponents/Layout/Page";
import Sort              from "@dashboardComponents/functions/sort";

import { MatchsList }      from "./MatchsList";
import { MatchFormulaire } from "./MatchForm";

const URL_DELETE_ELEMENT = 'api_matchs_delete';
const URL_DELETE_GROUP = 'api_matchs_delete_group';
const MSG_DELETE_ELEMENT = 'Supprimer ce match ?';
const MSG_DELETE_GROUP = 'Aucun match sélectionné.';
const SORTER = Sort.compareStartAt;

function searchFunction(dataImmuable, search){
    let newData = [];
    search = search.toLowerCase();
    newData = dataImmuable.filter(function(v) {
        if(v.team.toLowerCase().startsWith(search) || v.versus.toLowerCase().startsWith(search)){
            return v;
        }
    })

    return newData;
}

export class Matchs extends Component {
    constructor(props) {
        super(props);

        this.state = {
            perPage: 10,
            sessionName: "matchs.pagination"
        }

        this.layout = React.createRef();

        this.handleGetData = this.handleGetData.bind(this);
        this.handleUpdateList = this.handleUpdateList.bind(this);
        this.handleDelete = this.handleDelete.bind(this);
        this.handleDeleteGroup = this.handleDeleteGroup.bind(this);
        this.handleSearch = this.handleSearch.bind(this);

        this.handleContentList = this.handleContentList.bind(this);
        this.handleContentCreate = this.handleContentCreate.bind(this);
        this.handleContentUpdate = this.handleContentUpdate.bind(this);
    }

    handleGetData = (self) => { self.handleSetDataPagination(this.props.donnees, SORTER); }

    handleUpdateList = (element, newContext=null) => { this.layout.current.handleUpdateList(element, newContext, SORTER); }

    handleDelete = (element) => {
        this.layout.current.handleDelete(this, element, Routing.generate(URL_DELETE_ELEMENT, {'id': element.id}), MSG_DELETE_ELEMENT);
    }

    handleDeleteGroup = () => {
        this.layout.current.handleDeleteGroup(this, Routing.generate(URL_DELETE_GROUP), MSG_DELETE_GROUP);
    }

    handleSearch = (search) => { this.layout.current.handleSearch(search, searchFunction) }

    handleContentList = (currentData, changeContext) => {
        return <MatchsList onChangeContext={changeContext}
                           onDelete={this.handleDelete}
                           onSearch={this.handleSearch}
                           onDeleteAll={this.handleDeleteGroup}
                           data={currentData} />
    }

    handleContentCreate = (changeContext) => {
        return <MatchFormulaire type="create" onChangeContext={changeContext} onUpdateList={this.handleUpdateList}/>
    }

    handleContentUpdate = (changeContext, updateList, element) => {
        return <MatchFormulaire type="update" element={element} onChangeContext={changeContext} onUpdateList={this.handleUpdateList}/>
    }

    render () {
        return <>
            <Layout ref={this.layout} {...this.state} onGetData={this.handleGetData}
                    onContentList={this.handleContentList}
                    onContentCreate={this.handleContentCreate} onContentUpdate={this.handleContentUpdate}/>
        </>
    }
}