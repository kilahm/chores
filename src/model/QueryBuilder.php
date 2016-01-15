<?hh // strict

namespace kilahm\chores\model;

type FieldsAndValues = shape(
    'field list' => string,
    'value list' => string,
    'params' => Map<arraykey, string>,
);

abstract class QueryBuilder
{
    public static function mapToFieldsAndValues(Map<arraykey, string> $data) : FieldsAndValues
    {
        $fields = $data->keys();

        return shape(
            'field list' => implode(', ', $fields->map($f ==> '"' . $f . '"')),
            'value list' => implode(', ', $fields->map($f ==> ':' . $f)),
            'params' => self::mapToParams($data),
        );
    }

    public static function mapToUpdateList(Map<arraykey, string> $data) : (string, Map<arraykey, string>)
    {
        return tuple(
            implode(', ',  $data->mapWithKey(($field, $value) ==> "\"$field\" = :$field")),
            self::mapToParams($data),
        );
    }

    private static function mapToParams(Map<arraykey, string> $data) : Map<arraykey, string>
    {
        $params = Map{};
        foreach($data as $field => $value) {
            $params->set(':' . $field, $value);
        }
        return $params;
    }
}
