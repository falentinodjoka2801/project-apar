class LimitData extends React.Component{
     state     =    {
          limitValue     :    10,
          listValue      :    [1, 2, 5, 10, 15, 25, 50, 100]
     }
     limitValueChange    =    (e)  =>   {
          let tP    =    this.props;

          let limitValue      =    (e.target.value <= 0)? 1 : e.target.value;
          this.setState({limitValue});
          tP.componentValue(limitValue);
     }
     render    =    ()   =>   {
          let tS    =    this.state;
          let tP    =    this.props;

          return(
               <div className={'limit-data'}>
                    {tP.label.length >= 1 &&
                         <label className={'d-block'} style={{fontSize:'10.5pt'}}>{tP.label}</label>
                    }
                    <div className="input-group">
                         <select name="limitData" className="form-control" value={tS.limitValue}
                              onChange={this.limitValueChange}>
                              {tS.listValue.map((value, index) => {
                                   return    <option value={value} key={index}>{value}</option>
                              })}
                              {tS.listValue.indexOf(tS.limitValue) === -1 &&
                                   <option value={tS.limitValue}>{tS.limitValue}</option>
                              }
                         </select>
                         <input type="number" placeholder="Limit Data" className="form-control" 
                              value={tS.limitValue} onChange={this.limitValueChange} />
                    </div>
               </div>
          );
     }
}

LimitData.defaultProps      =    {
     componentValue :    ()   =>   {},
     label     :    ''
}