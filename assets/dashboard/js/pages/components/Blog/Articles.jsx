import React, { Component } from 'react';

import Routing           from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import { Layout }         from "@dashboardComponents/Layout/Page";
import Sort              from "@dashboardComponents/functions/sort";

import { ArticlesList }      from "./ArticlesList";
import { ArticleFormulaire } from "./ArticleForm";

const URL_DELETE_ELEMENT = 'api_articles_delete';
const URL_DELETE_GROUP = 'api_articles_delete_group';
const MSG_DELETE_ELEMENT = 'Supprimer cet article ?';
const MSG_DELETE_GROUP = 'Aucun article sélectionné.';
const URL_SWITCH_ELEMENT = 'api_articles_article_published';
const MSG_SWITCH_ELEMENT = 'Article';
const SORTER = Sort.compareCreatedAt;

function searchFunction(dataImmuable, search){
    let newData = [];
    newData = dataImmuable.filter(function(v) {
        if(v.title.toLowerCase().includes(search)){
            return v;
        }
    })

    return newData;
}

export class Articles extends Component {
    constructor(props) {
        super(props);

        this.state = {
            perPage: 10,
            categories: JSON.parse(props.categories),
            sessionName: "blog.pagination"
        }

        this.layout = React.createRef();

        this.handleGetData = this.handleGetData.bind(this);
        this.handleUpdateList = this.handleUpdateList.bind(this);
        this.handleDelete = this.handleDelete.bind(this);
        this.handleDeleteGroup = this.handleDeleteGroup.bind(this);
        this.handleSearch = this.handleSearch.bind(this);
        this.handleSwitchPublished = this.handleSwitchPublished.bind(this);

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

    handleSwitchPublished = (element) => {
        this.layout.current.handleSwitchPublished(this, element, Routing.generate(URL_SWITCH_ELEMENT, {'id': element.id}), MSG_SWITCH_ELEMENT);
    }

    handleContentList = (currentData, changeContext) => {
        return <ArticlesList onChangeContext={changeContext}
                             onDelete={this.handleDelete}
                             onSearch={this.handleSearch}
                             onDeleteAll={this.handleDeleteGroup}
                             onChangePublished={this.handleSwitchPublished}
                             data={currentData} />
    }

    handleContentCreate = (changeContext) => {
        return <ArticleFormulaire type="create" categories={this.state.categories} onChangeContext={changeContext} onUpdateList={this.handleUpdateList}/>
    }

    handleContentUpdate = (changeContext, updateList, element) => {
        return <ArticleFormulaire type="update" categories={this.state.categories} element={element} onChangeContext={changeContext} onUpdateList={this.handleUpdateList}/>
    }

    render () {
        return <>
            <Layout ref={this.layout} {...this.state} onGetData={this.handleGetData}
                    onContentList={this.handleContentList}
                    onContentCreate={this.handleContentCreate} onContentUpdate={this.handleContentUpdate}/>
        </>
    }
}