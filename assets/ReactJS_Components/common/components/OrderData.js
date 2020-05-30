class OrderData extends React.Component{
     state     =    {
          orderOrientation    :    'ASC',
          fields    :    [],
          selectedField  :    ''
     }
     componentDidMount   =    ()   =>   {
          let tP    =    this.props;

          this.setState({fields : tP.fields});
     }
     render    =    ()   =>   {
          let tS    =    this.state;
          let tP    =    this.props;

          return(
               <div className={'order-data'}>
                    {tP.label.length >= 1 &&
                         <label className={'d-block'} style={{fontSize:'10.5pt'}}>{tP.label}</label>
                    }
                    <div className={"input-group"}>
                         <select name="orderField" className={"form-control"} value={tS.selectedField}
                              onChange={(e) => {
                                   this.setState({selectedField : e.target.value});
                                   tP.componentValue(e.target.value, tS.orderOrientation);
                              }}>
                              <option value="">Order By Fields</option>
                              {tS.fields.map((f, indexF) => {
                                   return <option value={f.value} key={indexF}>{f.text}</option>
                              })}
                         </select>
                         <select name="orderOrientation" className={"form-control"} value={tS.orderOrientation}
                              onChange={(e) => {
                                   this.setState({orderOrientation : e.target.value});
                                   tP.componentValue(tS.selectedField, e.target.value);
                              }}>
                              <option value={"ASC"}>ASC (A - Z)</option>
                              <option value={"DESC"}>DESC (Z - A)</option>
                         </select>
                    </div>
               </div>
          );
     }
}

OrderData.defaultProps      =    {
     fields         :    [
          {text : 'Something Appear 1', value : 'The Value 1'},
          {text : 'Something Appear 2', value : 'The Value 2'},
          {text : 'Something Appear 3', value : 'The Value 3'}
     ],
     componentValue :    ()   =>   {},
     label     :    ''
}