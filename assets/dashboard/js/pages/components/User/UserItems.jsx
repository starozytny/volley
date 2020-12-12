import React from 'react';

import { ButtonIcon } from "@dashboardComponents/Tools/Button";

export function UserItems (props) {
    const { data, onChangeContext } = props

    return <div className="items items-user">
        {data.map(elem => {
            return <div className="item" key={elem.id}>

                <div className="item-selector" />

                <div className="item-content">
                    <div className="item-body">
                        <div className="avatar">
                            <img src={`https://robohash.org/${elem.username}?size=64x64`} alt={`Avatar de ${elem.username}`}/>
                        </div>
                        <div className="infos">
                            <div>
                                <div className="username">
                                    <span>{elem.username}</span>
                                    {elem.highRoleCode !== 0 && <span className="role">{elem.highRole.toLowerCase()}</span>}
                                </div>
                                <div className="email">{elem.email}</div>
                            </div>
                            <div className="actions">
                                {elem.highRoleCode !== 1 &&
                                    <>
                                        <ButtonIcon icon={"pencil"} onClick={() => onChangeContext("update", elem)}>Modifier</ButtonIcon>
                                        <ButtonIcon icon={"trash"} onClick={() => onChangeContext("delete")}>Supprimer</ButtonIcon>
                                        <ButtonIcon icon={"share"} onClick={() => onChangeContext("impersonate")}>Impersonate</ButtonIcon>
                                    </>
                                }
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        })}
    </div>
}