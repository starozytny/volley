import React, { Component } from 'react';

import Routing           from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import {Layout }         from "@dashboardComponents/Layout/Page";
import Sort              from "@dashboardComponents/functions/sort";
import Formulaire        from "@dashboardComponents/functions/Formulaire";

import { CategoriesList }     from "./CategoriesList";
import { CategoryFormulaire } from "./CategoryForm";

function searchFunction(dataImmuable, search){
    let newData = [];
    newData = dataImmuable.filter(function(v) {
        if(v.name.toLowerCase().includes(search)){
            return v;
        }
    })

    return newData;
}

export class Categories extends Component {
    constructor(props) {
        super(props);

        this.state = {
            perPage: 10
        }

        this.layout = React.createRef();

        this.handleDelete = this.handleDelete.bind(this);
        this.handleSearch = this.handleSearch.bind(this);
        this.handleDeleteGroup = this.handleDeleteGroup.bind(this);

        this.handleGetData = this.handleGetData.bind(this);
        this.handleContentList = this.handleContentList.bind(this);
        this.handleContentCreate = this.handleContentCreate.bind(this);
        this.handleContentUpdate = this.handleContentUpdate.bind(this);
    }

    handleGetData = (self) => { Formulaire.axiosGetDataPagination(self, Routing.generate('api_blog_categories_index'), Sort.compareName, this.state.perPage) }

    handleContentList = (currentData, changeContext) => {
        return <CategoriesList onChangeContext={changeContext}
                               onDelete={this.handleDelete}
                               onSearch={this.handleSearch}
                               onDeleteAll={this.handleDeleteGroup}
                               data={currentData} />
    }

    handleContentCreate = (changeContext, updateList) => {
        return <CategoryFormulaire onChangeContext={changeContext} onUpdateList={updateList}/>
    }

    handleContentUpdate = (changeContext, updateList, element) => {
        return <CategoryFormulaire element={element} onChangeContext={changeContext} onUpdateList={updateList}/>
    }

    handleDelete = (element) => {
        Formulaire.axiosDeleteElement(this, element, Routing.generate('api_blog_categories_delete', {'id': element.id}),
            'Supprimer cette catégorie ?', 'Cette action est irréversible.');
    }
    handleDeleteGroup = () => {
        let checked = document.querySelectorAll('.i-selector:checked');
        Formulaire.axiosDeleteGroupElement(this, checked, Routing.generate('api_blog_categories_delete_group'), 'Aucune catégorie sélectionnée.')
    }

    handleSearch = (search) => { this.layout.current.handleSearch(search, searchFunction) }

    render () {
        return <>
            <Layout ref={this.layout} {...this.state} onGetData={this.handleGetData}
                    onContentList={this.handleContentList}
                    onContentCreate={this.handleContentCreate} onContentUpdate={this.handleContentUpdate}/>
        </>
    }
}


// export class Categories extends Component {
//
//
//     handleUpdateList = (element, newContext=null) => {
//         const { data, context, perPage } = this.state
//         Formulaire.updateDataPagination(this, Sort.compareName, newContext, context, data, element, perPage);
//     }
// }