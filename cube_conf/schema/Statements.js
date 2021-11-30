cube(`Statements`, {
  sql: `SELECT * FROM lrs.trax_xapi_statements`,

  title: ` `,

  joins: {},

  measures: {
    count: {
      type: `count`,
      drillMembers: [id, actorMbox, actorName, actorObjecttype, authorityName, authorityObjecttype, 
      objectDefinitionInteractiontype, objectDefinitionNameUnd, objectDefinitionType, objectId,
      objectObjecttype, resultScoreRaw, resultSuccess, resultResponse, verbId, verbDisplayEnUs,
      stored, timestamp, contextTeamAccountName, actorAccountName, contextExtensionsLocation],
      title: `Statements`
    },
    ScoreAll: {
      sql: `${CUBE}.data->>'$.result.score.scaled' * 100`,
      type: `number`
    },
    coursesAVGScore: {
      sql: `${CUBE}.data->>'$.result.score.scaled' * 100`,
      type: `avg`,
      title: `Average Score`
    },
    observersAllScore: {
      sql: `${CUBE}.data->>'$.result.score.scaled' * 100`,
      type: `number`
    },
    observersAVGScore: {
      sql: `${CUBE}.data->>'$.result.score.scaled' * 100`,
      type: `avg`,
	  filters: [{ sql: `${CUBE}.data->>'$.verb.display."en-US"' = 'completed'` }],
    },
    Users: {
      sql: `${CUBE}.data->>'$.actor.name'`,
      type: `countDistinct`,
      title: `Number of Distinct Users`
    },
    Groups: {
      sql: `${CUBE}.data->>'$.context.team.account.name'`,
      type: `countDistinct`,
      title: `Number of Distinct Groups`
    },
	Courses: {
      sql: `${CUBE}.data->>'$.object.definition.name.und'`,
      type: `countDistinct`,
      filters: [{ sql: `${CUBE}.data->>'$.verb.display."en-US"' = 'passed' && ${CUBE}.data->>'$.object.definition.name.und' != 'Untitled Quiz'` }],
	  title: `Number of Distinct Courses`
    },
    Objectives: {
      sql: `${CUBE}.data->>'$.object.definition.name.und'`,
      type: `countDistinct`,
      title: `Number of Distinct Objectives`
    },
	Sources: {
      sql: `${CUBE}.data->>'$.authority.account.name'`,
      type: `countDistinct`,
      title: `Number of Distinct Sources`
    }
  },

  dimensions: {
    id: {
      sql: `${CUBE}.data->>'$.id'`,
      type: `string`,
      title: `ID`,
      primaryKey: true
    },

    actorMbox: {
      sql: `${CUBE}.data->>'$.actor.mbox'`,
      type: `string`,
      title: `Actor Mbox`
    },

    actorName: {
      sql: `${CUBE}.data->>'$.actor.name'`,
      type: `string`,
      title: `Actor Name`
    },

    actorAccountName: {
      sql: `${CUBE}.data->>'$.actor.account.name'`,
      type: `string`,
      title: `Actor Account Name`
    },

    actorObjecttype: {
      sql: `${CUBE}.data->>'$.actor.objectType'`,
      type: `string`,
      title: `Actor Object Type`
    },
    
    authorityName: {
      sql: `${CUBE}.data->>'$.authority.account.name'`,
      type: `string`,
      title: `Authority Name`
    },
    
    authorityObjecttype: {
      sql: `${CUBE}.data->>'$.authority.objectType'`,
      type: `string`,
      title: `Authority Object Type`
    },
    
    objectDefinitionInteractiontype: {
      sql: `${CUBE}.data->>'$.object.definition.interactionType'`,
      type: `string`,
      title: `Object Definition Interaction Type`
    },
    
    objectDefinitionNameEn: {
      sql: `${CUBE}.data->>'$.object.definition.name."en-US"'`,
      type: `string`,
      title: `Object Definition Name`
    },
    objectDefinitionNameUnd: {
      sql: `${CUBE}.data->>'$.object.definition.name.und'`,
      type: `string`,
      title: `Object Definition Name`
    },

    objectDefinitionNameAll: {
      sql: `${CUBE}.data->>'$.object.definition.name.*'`,
      type: `string`,
      title: `Object Definition All Lang`
    },
    
    objectDefinitionType: {
      sql: `${CUBE}.data->>'$.object.definition.type'`,
      type: `string`,
      title: `Object Definition Type`
    },
    
    objectId: {
      sql: `${CUBE}.data->>'$.object.id'`,
      type: `string`,
      title: `Object ID`
    },
    
    objectObjecttype: {
      sql: `${CUBE}.data->>'$.object.objectType'`,
      type: `string`,
      title: `Object Type`
    },
	contextContextActivitiesParent: {
      sql: `${CUBE}.data->>'$.context.contextActivities.parent[0].definition.name."en-US"'`,
      type: `string`,
      title: `Context Parent`
    },

    resultScoreRaw: {
      sql: `${CUBE}.data->>'$.result.score.raw'`,
      type: `string`,
      title: `Result Score`
    },
	
	resultScoreScaled: {
      sql: `${CUBE}.data->>'$.result.score.scaled' * 100`,
      type: `string`,
      title: `Result Score`
    },
    resultDuration: {
      sql: `${CUBE}.data->>'$.result.duration'`,
      type: `string`,
      title: `Result Duration`
    },

    resultSuccess: {
      sql: `${CUBE}.data->>'$.result.success'`,
      type: `string`,
      title: `Result Success`
    },
    
    resultResponse: {
	  sql: `${CUBE}.data->>'$.result.response'`,
      type: `string`,
      title: `Result Response`
    },

    verbId: {
      sql: `${CUBE}.data->>'$.verb.id'`,
      type: `string`,
      title: `Verb ID`
    },

    verbDisplayEnUs: {
      sql: `${CUBE}.data->>'$.verb.display."en-US"'`,
      type: `string`,
      title: `Verb Display EnUs`
    },

    verbDisplayEn: {
      sql: `${CUBE}.data->>'$.verb.display.*'`,
      type: `string`,
      title: `Verb Display`
    },

    contextTeamAccountName: {
      sql: `${CUBE}.data->>'$.context.team.account.name'`,
      type: `string`,
      title: `Team Name`
    },

    contextExtensionsLocation: {
      sql: `${CUBE}.data->>'$.context.extensions."http://id.tincanapi.com/extension/location"'`,
      type: `string`,
      title: `Location`
    },

    stored: {
      sql: `${CUBE}.data->>'$.stored'`,
      type: `time`,
      title: `Stored`
    },
    timestamp: {
      sql: `${CUBE}.data->>'$.timestamp'`,
      type: `time`,
      title: `Timestamp`
    },
  Course: {
    type: `number`,
    case: {
     when: [
      { sql: `${CUBE}.data->>'$.verb.display."en-US"' = 'passed' || ${CUBE}.data->>'$.verb.display."en-US"' = 'failed'`, label: {sql:`${CUBE}.data->>'$.object.definition.name.und'`} },
     ],
    }
  },
  },
  dataSource: `default`
});
